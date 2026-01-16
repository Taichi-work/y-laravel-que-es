# 🧠 AI Quiz Generator - クイズ出題アプリ

Googleの最新AI **Gemini 2.5 Flash** を活用し、指定したジャンルのクイズをリアルタイムで自動生成するアプリケーションです。

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.5-blue?logo=php)
![Gemini API](https://img.shields.io/badge/Gemini_API-2.5_Flash-4285F4?logo=google-gemini)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.0-06B6D4?logo=tailwindcss)

---

## 📋 機能

### ✅ 実装済み機能

#### 🤖 AIクイズ生成
- 指定したジャンルに基づき、Gemini 2.5 Flashが4択問題を自動生成
- 生成されたクイズの即時出題・採点機能
- AIによる正解の解説表示

#### 📊 クイズ管理・履歴
- クイズ履歴の一覧表示（Read）
- 過去の挑戦結果の保存（Create）
- 履歴の削除機能（Delete）

#### 🔐 ユーザー認証
- Laravel Breezeによるサインアップ・ログイン機能
- ユーザーごとの学習データ管理

### 🚀 こだわって実装した機能
- **最新AIモデルの選定**: 
    - 1.5/2.0系モデルでのAPI Quota制限（limit: 0）を徹底的に検証。2026年現在の最新かつ安定モデルである **Gemini 2.5 Flash** を特定し、実装に成功しました。
- **技術的課題の解決**:
    - APIリクエスト時のモデル名不一致やバージョン（v1/v1beta）によるエラーを、`curl` を用いたパケットレベルのデバッグにより解消しました。

---

## 🛠️ 技術スタック

| カテゴリ | 技術 |
|:---|:---|
| **フレームワーク** | Laravel 12 |
| **言語** | PHP 8.5 |
| **AI モデル** | **Gemini 2.5 Flash** |
| **データベース** | MySQL 8.0 |
| **フロントエンド** | Blade + Tailwind CSS |
| **開発環境** | Docker + Laravel Sail |

---

## 🎓 学習目的

このプロジェクトは以下の学習を目的として作成されました：

### Laravel におけるMVCアーキテクチャの高度な活用
- ビジネスロジックをControllerに集約しつつ、モデルでのデータ管理を徹底。
- Bladeテンプレートを用いた動的なUI表示の制御。

### 外部API（Google Gemini）との非同期・動的連携
- **Gemini 2.5 Flash** を採用し、ユーザーの入力に応じてリアルタイムでコンテンツを生成する仕組みを構築。
- APIレスポンス（JSON）のパースとエラーハンドリングの実装。

### APIのレートリミットやエラーハンドリングの実践的なデバッグ

---

## 🚀 セットアップ

### 📦 必要要件
- Docker Desktop
- Git

### ⚙️ インストール手順

#### 1. リポジトリをクローン
```bash
git clone [https://github.com/your-username/ai-quiz-generator.git](https://github.com/your-username/ai-quiz-generator.git)
cd ai-quiz-generator

#### 2. 依存関係をインストール
Bash

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs
#### 3. .envファイルを作成
Bash

cp .env.example .env
#### 4. APIキーを設定
.env ファイルを開き、取得したGemini APIキーを設定します。

コード スニペット

GEMINI_API_KEY=your_actual_api_key_here
#### 5. Sailを起動
Bash

./vendor/bin/sail up -d
#### 6. 初期設定（キー生成 & マイグレーション）
Bash

./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
#### 7. 設定キャッシュのクリア
Bash

./vendor/bin/sail artisan config:clear
#### 8. ブラウザでアクセス
以下のURLをコピーしてブラウザで開いてください。

Plaintext

http://localhost
