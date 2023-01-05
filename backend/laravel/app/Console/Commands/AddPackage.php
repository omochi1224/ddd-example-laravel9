<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddPackage extends Command
{
    private const PACKAGES_PATH = 'Packages/';
    private const TESTING_PATH = 'tests/';
    private const GIT_KEEP = '.gitkeep';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:feature';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '機能ごとにパッケージディレクトリを追加します。';

    /**
     * @var string|null 機能名
     */
    private ?string $packageName;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->packageName = $this->ask('新しい機能名を入力してください。');
        if (is_null($this->packageName)) {
            $this->error('機能名は必須です。');
            return;
        }
        $this->info($this->packageName . 'を作成します。');

        if ($this->isExistDirectory()) {
            $this->error('すでに同じ機能名が作られています。');
            return;
        }

        $this->createDirectory();
    }

    /**
     * directoryの存在確認
     *
     * @return bool
     */
    private function isExistDirectory(): bool
    {
        return file_exists(self::PACKAGES_PATH . $this->packageName);
    }

    /**
     * ディレクトリ追加
     *
     * @return void
     */
    private function createDirectory(): void
    {
        mkdir(self::PACKAGES_PATH . $this->packageName, 0755, true);

        $dirs = [
            'Application' => ['Dtos', 'UseCases'],
            'Domain' => ['Exceptions', 'Models', 'Services'],
            'Infrastructure' => ['EloquentModels', 'QueryServices', 'Repositories', 'Factories'],
            'Presentation' => ['Requests', 'Controllers'],
        ];

        $this->createDirectoryFromArray(self::PACKAGES_PATH, $dirs);

        mkdir(self::TESTING_PATH . $this->packageName, 0755, true);
        $testingDirs = [
            'Feature' => ['Controller', 'UseCase'],
            'Unit' => ['Domain', 'ValueObject', 'Repository', 'Factory', 'Dto', 'Request'],
        ];

        $this->createDirectoryFromArray(self::TESTING_PATH, $testingDirs);
    }

    private function createDirectoryFromArray(string $path, array $dirs): void
    {
        foreach ($dirs as $key => $dir) {
            mkdir($p = $path . $this->packageName . '/' . $key, 0755, true);
            foreach ($dir as $value) {
                mkdir($p . '/' . $value, 0755, true);
                file_put_contents($p . '/' . $value . '/' . self::GIT_KEEP, '');
            }
        }
    }
}
