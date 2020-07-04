<?php


namespace Lson\Authorization\Traits;

trait HasPermission
{
    /**
     * All permissions
     *
     * @return mixed
     *
     * @author lsrong
     * @datetime 04/07/2020 11:11
     */
    public function allPermissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->merge(
            $this->permissions()->get()
        );
    }

    /**
     * Check if user is super administrator.
     *
     * @return mixed
     */
    public function isSuperAdministrator(): bool
    {
        return (bool) $this->is_super;
    }
}
