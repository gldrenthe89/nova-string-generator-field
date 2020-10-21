<?php

namespace Gldrenthe89\NovaStringGeneratorField\Traits;

trait NovaGenerateStringTrait
{
    /**
     * Set the length of the generated string: Default length is 10
     *
     * @param  int  $length
     * @return mixed
     */
    public function length(int $length)
    {
        return $this->withMeta([
            'length' => $length,
        ]);
    }

    /**
     * Set the excluded character rules: ['symbols', 'numbers'. 'uppercase', 'lowercase']
     *
     * @param  array  $rules
     * @return mixed
     */
    public function excludeRules(array $rules)
    {
        return $this->withMeta([
            'exclude_rules' => $rules,
        ]);
    }
}
