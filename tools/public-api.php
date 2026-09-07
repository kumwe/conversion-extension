<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
$root = dirname(__DIR__);
$composer = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$namespace = array_key_first($composer['autoload']['psr-4']);
$types = [];
foreach (glob($root . '/src/*.php') as $path) {
    $name = $namespace . basename($path, '.php');
    $type = new ReflectionClass($name);
    $methods = [];
    foreach ($type->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->getDeclaringClass()->getName() !== $name) { continue; }
        $parameters = [];
        foreach ($method->getParameters() as $parameter) {
            $row = ['name' => $parameter->getName(), 'type' => (string) $parameter->getType(), 'optional' => $parameter->isOptional(), 'by_reference' => $parameter->isPassedByReference(), 'variadic' => $parameter->isVariadic()];
            if ($parameter->isDefaultValueAvailable()) { $row['default'] = $parameter->getDefaultValue(); }
            $parameters[] = $row;
        }
        $methods[$method->getName()] = ['static' => $method->isStatic(), 'parameters' => $parameters, 'return' => (string) $method->getReturnType()];
    }
    ksort($methods);
    $properties = [];
    foreach ($type->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
        if ($property->getDeclaringClass()->getName() !== $name) { continue; }
        $properties[$property->getName()] = ['type' => (string) $property->getType(), 'readonly' => $property->isReadOnly()];
    }
    ksort($properties);
    $constants = [];
    foreach ($type->getReflectionConstants(ReflectionClassConstant::IS_PUBLIC) as $constant) {
        $value = $constant->getValue();
        $constants[$constant->getName()] = $value instanceof BackedEnum ? $value->value : $value;
    }
    ksort($constants);
    $interfaces = $type->getInterfaceNames(); sort($interfaces);
    $types[$name] = ['kind' => $type->isEnum() ? 'enum' : ($type->isInterface() ? 'interface' : 'class'), 'final' => $type->isFinal(), 'readonly' => $type->isReadOnly(), 'interfaces' => $interfaces, 'constants' => $constants, 'properties' => $properties, 'methods' => $methods];
}
ksort($types);
require __DIR__.'/manifest-profile.php';
$profile=packageProfile($types,$composer,$root);
$documents=['resources/public-api/v1.json'=>$profile,'resources/public-api/details.json'=>$types]+packageSupportProfiles($profile,$composer);
foreach($documents as $path=>$data){$bytes=json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR)."\n";if(in_array('--write',$argv,true))file_put_contents($root.'/'.$path,$bytes);elseif(!is_file($root.'/'.$path)||file_get_contents($root.'/'.$path)!==$bytes)throw new RuntimeException('Manifest drift: '.$path);}
echo count($types) . " public types verified.\n";
