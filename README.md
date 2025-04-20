# 勤怠管理アプリ
## 環境構築
Dockerビルド
1. git clone git@github.com:ami524/attendance-management.git
2. docker-compose up -d --build
* MySQLは、OSによって起動しない場合があるので各々PCに合わせてdocker-compose.ymlファイルを編集してください。

Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed

## 使用技術
* PHP: ^7.3 or ^8.0
* Laravel: ^8.75
* MySQL（バージョンは.envのDB設定に依存）

### 主なライブラリ・パッケージ
- Laravel Sanctum（API認証）
- Laravel Fortify（認証機能）
- Laravel Tinker（CLIツール）
- Fruitcake Laravel CORS（CORS設定）
- Guzzle HTTP（外部API通信）
- Laravel Lang（多言語対応）

## URL
* 開発環境：http://localhost/
* phpMyAdmin:http://localhost:8080/
