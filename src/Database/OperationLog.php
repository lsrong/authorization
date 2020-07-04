<?php


namespace Lson\Authorization\Database;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationLog extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'path', 'method', 'ip', 'input'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('authorization.operation_log_table'));

        parent::__construct($attributes);
    }

    /**
     * User relationship
     *
     * @return BelongsTo
     *
     * @author lsrong
     * @datetime 03/07/2020 17:47
     */
    public function user(): BelongsTo
    {
        $user_model = config('authorization.database.users_model');

        return $this->belongsTo($user_model, 'user_id');
    }
}
