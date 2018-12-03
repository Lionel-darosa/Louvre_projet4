<?php

namespace App\Tests\Validator\Constraints;

use App\Validator\Constraints\MoreThanThousand;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class MoreThanThousandTest
 * @package App\Tests\Validator\Constraints
 */
class MoreThanThousandTest extends TestCase
{
    public function testGetTargets()
    {
        $constraint = new MoreThanThousand();

        $this->assertEquals(MoreThanThousand::CLASS_CONSTRAINT, $constraint->getTargets());
    }
}