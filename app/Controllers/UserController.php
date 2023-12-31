<?php

namespace App\Controllers;

use App\Entities\User;
use App\Repositories\UserRepository;
use App\Exceptions\BadRequestException;
use App\Exceptions\UserNotFoundException;
use Exception;
use Src\Exceptions\UnauthenticatedException;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function index()
    {
        return $this->response($this->repository->index());
    }

    public function create()
    {
        $request = request();
        if (!$request->name || empty($request->name)) {
            throw new BadRequestException('Campo name é obrigatório');
        }
        return $this->response($this->repository->save($request->all()));
    }

    public function update(int $id)
    {
        $request = request();
        return $this->response($this->repository->update($id, $request->all()));
    }

    public function delete(int $id)
    {
        return $this->response($this->repository->delete($id));
    }

    public function getWithContacts(int $id)
    {
        return $this->response($this->repository->getWithContacts($id));
    }

    public function findByName(string $name)
    {
        $user = $this->repository->findByName($name);
        if (!$user) $this->response('User not found', 404);
        return $this->response($user);
    }

    /**
     * thats is a very simple implementation and not can be used in a real application
     *
     * @return void
     */
    public function auth()
    {
        try {
            $user = new User();
            $user->get(request()->id);
            $this->response($user->auth());
        } catch (UserNotFoundException $e) {
            throw new UnauthenticatedException();
        }
    }
}
