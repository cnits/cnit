<?php
    /**
     * http://php.net/manual/en/language.oop5.traits.php
     */
    trait SayHello
    {
        function sayHow()
        {
            echo "Ok, let say: 'Hello' ";
        }

        function say() {
            echo "Ok! Traits.";
        }
    }

    /**
     * http://php.net/manual/en/language.oop5.traits.php
     */
    trait SayWhat
    {
        function sayHow()
        {
            echo "Say How in Say What";
        }
    }
    

    class Greeting {
        use SayHello;
    }

    class Compliment {
        use SayHello, SayWhat {
            SayHello::sayHow insteadOf SayWhat;
            SayWhat::sayHow as talk;
        }
    }

    $s = new Greeting();
    $s->say();

    $c = new Compliment();
    $c->talk();
    
?>