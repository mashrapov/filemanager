<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createUser';
    protected $description = 'Создает нового пользователя';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('Введите имя пользователя');
        $email = $this->ask('Введите email пользователя');
        $password = $this->secret('Введите пароль пользователя');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->info("Пользователь {$user->name} создан успешно.");
    }
}
