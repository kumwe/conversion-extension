<?php
declare(strict_types=1);

/** Generate the consumer-facing Version 2 profile from reflected package declarations. */
function packageProfile(array $symbols, array $composer, string $root): array
{
    preg_match('/^##\\s+(\\d+\\.\\d+\\.\\d+)\\b/m', file_get_contents($root.'/CHANGELOG.md'), $match);
    if (!isset($match[1])) throw new RuntimeException('A stable changelog release record is required.');
    $namespace = array_key_first($composer['autoload']['psr-4']);
    $public = [];
    foreach ($symbols as $name => $original) {
        $type = new ReflectionClass($name);
        $constants = [];
        foreach ($type->getReflectionConstants(ReflectionClassConstant::IS_PUBLIC) as $constant) {
            if ($constant->getDeclaringClass()->getName() !== $name) continue;
            $constants[$constant->getName()] = ['type'=>$constant->hasType()?(string)$constant->getType():null];
        }
        $properties = [];
        foreach ($type->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->getDeclaringClass()->getName() !== $name) continue;
            $properties[$property->getName()]=['type'=>$property->hasType()?(string)$property->getType():null,'static'=>$property->isStatic(),'readonly'=>$property->isReadOnly()];
        }
        $methods = [];
        foreach ($type->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() !== $name) continue;
            $parameters=[];
            foreach ($method->getParameters() as $parameter) $parameters[]=[
                'name'=>$parameter->getName(),'type'=>$parameter->hasType()?(string)$parameter->getType():null,
                'optional'=>$parameter->isOptional(),'variadic'=>$parameter->isVariadic(),'by_reference'=>$parameter->isPassedByReference(),
            ];
            $methods[$method->getName()]=['visibility'=>'public','static'=>$method->isStatic(),'parameters'=>$parameters,'return'=>$method->hasReturnType()?(string)$method->getReturnType():null];
        }
        ksort($constants);ksort($properties);ksort($methods);$interfaces=$type->getInterfaceNames();sort($interfaces);
        $public[$name]=[
            'kind'=>$type->isEnum()?'enum':($type->isInterface()?'interface':'class'),'stability'=>'stable',
            'file'=>substr($type->getFileName(),strlen($root)+1),'abstract'=>$type->isAbstract(),
            'final'=>$type->isFinal(),'readonly'=>$type->isReadOnly(),'parent'=>($type->getParentClass()?:null)?->getName(),
            'interfaces'=>$interfaces,'constants'=>(object)$constants,'properties'=>(object)$properties,'methods'=>(object)$methods,'deprecated'=>null,
        ];
    }
    ksort($public);
    return ['schema'=>'kumwe-package-public-api/v1','package'=>$composer['name'],'release'=>$match[1],
        'namespace'=>$namespace,'symbols'=>$public,
        'extension_points'=>array_keys(array_filter($public,static fn($v)=>$v['kind']==='interface')),
        'digest_of'=>'resources/public-api/v1.json'];
}

/** Generate the service/capability contracts without executing factories or host code. */
function packageSupportProfiles(array $api, array $composer): array
{
    $namespace=$api['namespace'];$providerName=$namespace.'ConfigProvider';
    $hasProvider=isset($api['symbols'][$providerName]);
    $provider=$hasProvider?(new $providerName())():[];
    $factories=[];
    foreach(($provider['dependencies']['factories']??[]) as $service=>$factory)$factories[]=[
        'service'=>$service,'factory'=>$factory,'lifetime'=>($provider['dependencies']['shared'][$service]??true)?'shared':'non-shared',
    ];
    $keys=[];$slug=substr($composer['name'],6);
    foreach(($provider['kumwe'][$slug]??[]) as $key=>$default)$keys[]=[
        'key'=>'kumwe.'.$slug.'.'.$key,'default'=>$default,'description'=>'Explicit '.$slug.' configuration; see docs/integration.md.',
    ];
    $services=['schema'=>'kumwe-package-service-map/v1','package'=>$composer['name'],'release'=>$api['release'],
        'config_provider'=>$hasProvider?$providerName:null,
        'provider_absence_reason'=>$hasProvider?null:'Immutable declarations and stateless helpers use direct construction; no injected runtime service is exported.',
        'factories'=>$factories,'aliases'=>(object)($provider['dependencies']['aliases']??[]),'delegators'=>[],'configuration_keys'=>$keys];
    $capabilities=['schema'=>'kumwe-package-capabilities/v1','package'=>$composer['name'],'release'=>$api['release'],
        'namespace'=>$namespace,'responsibility'=>$composer['description'],
        'non_responsibilities'=>['Host trust, final authorization and active generation selection','Persistence, durable transactions, transport and worker lifecycle'],
        'capabilities'=>[['id'=>$slug.'.portable','title'=>$composer['description'],'description'=>'Canonical portable declarations, behavior and explicit construction; the host retains operational authority.',
            'symbols'=>array_keys($api['symbols']),'documentation'=>['docs/public-api.md','docs/architecture.md','docs/integration.md']]],
        'native_requirements'=>null,'deprecations'=>[]];
    return ['resources/capabilities/v1.json'=>$capabilities,'resources/service-map/v1.json'=>$services];
}
