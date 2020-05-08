<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Arr;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version April 2, 2020, 4:32 pm UTC
*/

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function newUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    public function updateUser($data, $id)
    {
        $password = Arr::get($data, 'password');
        if ($password && !empty($password)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        return $this->update($data, $id);
    }
}
