<?php
    /**
     * @author Philippe Hugo <info@phlhg.ch>
     * @copyright Philippe Hugo
     */

    namespace App\Models\Account;

    /**
     * Defines enumarations for the status of the relation.
     */
    class Relation {

        const STRANGER = 0;
        const REQUESTED = 1;
        const FRIENDS = 2;
        const FOLLOWING = 2;
        const ME = 9;

    }

?>