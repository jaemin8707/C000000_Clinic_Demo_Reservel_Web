<?php

namespace Tests\Feature\batch;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Illuminate\Support\Facades\Artisan;

class BatchTestCase extends TestCase
{
    /**
     * セットアップ
     * バッチ実行準備
     * DBマイグレーション
     *
     * @return void
     */
    public function setUp() : void {

        parent::setUp();

        $this->test = $this->getTargetClass();
        $this->test->setLaravel($this->app);

        $app = new Application();
        $app->add($this->test);                         // ApplicationにCommandを登録

        $command = $app->find($this->name);
        $this->command = new CommandTester($command); // CommandTesterを被せる

        Artisan::call('migrate:refresh');

    }

    /**
     * artisan コマンドラッパー
     * パラメーターの詳細は CommandTester::execute() を参照
     *
     * @return string
     */
    protected function execute(array $input = [], $options = []) {
        $this->command->execute($input, $options);
        return $this->command->getDisplay();
    }

    /**
     * 起動対象クラスの取得
     *
     */
    protected function getTargetClass() {
        return null;         //  起動対象クラス
    }

}
