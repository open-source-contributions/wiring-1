<?php

namespace Wiring\Permissions\Acl;

interface UserAclInterface
{
    /**
     * @return \Wiring\Permissions\Acl\Role
     */
    public function getRole();

    /**
     * @return int
     */
    public function getId();
}
