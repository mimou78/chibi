<?php

namespace Chibi\Template;

use Chibi\Template\Compilers\ConditionCompiler;
use Chibi\Template\Compilers\PrintCompiler;

class Template {

    protected $file;

    protected $vars = [];

    protected $contents = "";

    protected $compilers = [
        PrintCompiler::class,
        ConditionCompiler::class,
    ];

    /**
     * Generate the file
     *
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Fill the variables
     *
     * @param array $vars
     * @return $this
     */
    public function fill($vars = [])
    {
        $vars = is_array($vars)? $vars : [$vars];
        $this->vars = $vars;

        return $this;
    }

    /**
     *  Parse the variables in the template
     *
     */
    protected function parseVariables()
    {
        if ($this->checkUnassignedVariables($value)) {
            throw new \Exception("variable <b>$".$value."</b> is undefined");
        }
        return $this;
    }


    /**
     * Compile the view
     *
     * @return $this
     */
    public function compile()
    {
        $this->parseVariables();
        $this->contents = file_get_contents($this->file);
        foreach($this->compilers as $compiler){
            $this->contents = (new $compiler($this->vars))->compile($this->contents);
        }
        return $this;
    }

    /**
     * Echo out the compiled code
     */
    public function render()
    {
        eval(' ?>' .$this->contents. '<?php ') ;
    }


    /**
     * Get the variables in the template
     *
     * @return array
     */
    protected function getTemplateVars() {
        $matches = [];
        preg_match_all('/{{\s*(\$(.*?))\s*}}/', $this->contents,$matches);
        return isset($matches[2]) ? $matches[2] : [];
    }

    /**
     * Check if there are some unassigned variables
     *
     * @param $value
     * @return bool
     */
    protected function checkUnassignedVariables(&$value) {
        $diff = array_diff($this->getTemplateVars(),array_keys($this->vars));
        if (count($diff) > 0)  {
            $value = array_values($diff)[0];
        }
        return count($diff) > 0;
    }
}