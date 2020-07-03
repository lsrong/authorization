<?php
namespace Lson\Authorization\Database;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;
use Illuminate\Auth\Authenticatable;

class User extends Base implements JWTSubject,AuthenticateContract
{
    use Authenticatable;

    /**
     * The attributes that should be file for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'name', 'avatar', 'remember_token',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('authorization.database.users_table'));

        parent::__construct($attributes);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}