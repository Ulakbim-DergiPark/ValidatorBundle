<?php

namespace Ojs\ValidatorBundle\Service;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\ValidatorBuilder;

class ValidatorService
{
    /** @var FileLocator */
    private $fileLocator;

    /** @var array */
    private $ruleSets;

    /**
     * ValidatorService constructor.
     * @param FileLocator $fileLocator
     */
    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
        $this->findRuleSets();
    }

    private function findRuleSets()
    {
        $directory = $this->fileLocator->locate('@ValidatorBundle/Resources/rulesets');

        $finder = new Finder();
        $finder->in($directory);

        $files = array_merge(
            iterator_to_array($finder->files()->name('*.yml')),
            iterator_to_array($finder->files()->name('*.xml'))
        );

        // In name => path format
        foreach ($files as $file) {
            $this->ruleSets[basename($file, '.' . pathinfo($file)['extension'])] = $file;
        }
    }

    /**
     * @return array
     */
    public function getRuleSets()
    {
        return $this->ruleSets;
    }

    /**
     * @param $name
     * @return \Symfony\Component\Validator\ValidatorInterface
     */
    public function getValidator($name)
    {
        if (!key_exists($name, $this->ruleSets)) {
            throw new \InvalidArgumentException('Rule set not found.');
        }

        $builder = new ValidatorBuilder();
        $file = $this->ruleSets[$name];
        $ext = pathinfo($file)['extension'];

        if ($ext === 'yml') {
            return $builder->addYamlMapping($file)->getValidator();
        } else if ($ext === 'xml') {
            return $builder->addXmlMappings($file)->getValidator();
        }

        throw new \UnexpectedValueException('Mappings must be in YAML or XML format.');
    }
}
