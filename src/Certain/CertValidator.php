<?php
/*
 * This file is part of the Certain package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Certain;

/**
 * CertValidator
 *
 *
 */
class CertValidator
{
    protected $cert;

    protected $parentValidator = false;

    protected $rules = array();

    public function __construct(Cert $cert)
    {
        $this->cert = $cert;

        if ($parent = $cert->getParent()) {
            $this->parentValidator = new self($parent);
        }
    }

    public function validate(Cert $cert, $returnErrors = false)
    {
        $rules = $this->getRuleList();
        $brokenRules = array();
        $rules = $this->getRuleList();

        foreach ($rules as $rule) {
            $results = $this->checkRule($rule, $cert);

            if ($results !== true) {
                $brokenRules[$rule] = $results;
            }
        }

        if ($parent = $cert->getParent()) {
            $parentValidate = $this->validate($parent, $returnErrors);
            if ($parentValidate !== true) {
                $brokenRules['parent'] = $parentValidate;
            }
        }

        if (count($brokenRules) < 1) {
            return true;
        }

        if ($returnErrors) {
            return $brokenRules;
        } else {
            return false;
        }
    }

    public function checkRule($rule, $cert)
    {
        $rule = $this->getRule($rule, $cert);

        if($rule->validate())

            return true;

        return $rule->getError();
    }

    public function getRule($ruleName, $cert)
    {
        $class = '\Certain\Validation\\' . $ruleName;

        return new $class($cert);
    }

    public function getRuleList()
    {
        // I'll throw some autogeneration crap in later, right now it's time for lazy
        return array();
    }

}
