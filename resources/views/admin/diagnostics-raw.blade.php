<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Диагностика проекта | vsearmyne.ru</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            color: #667eea;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 16px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card h2 {
            color: #667eea;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-connected {
            background: #10b981;
            color: white;
        }
        .status-error {
            background: #ef4444;
            color: white;
        }
        .status-unknown {
            background: #6b7280;
            color: white;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
            text-align: right;
        }
        .info-value code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .structure-section {
            margin-bottom: 20px;
        }
        .structure-section h3 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .structure-item {
            background: #f9fafb;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        .structure-item strong {
            color: #333;
            display: block;
            margin-bottom: 4px;
        }
        .structure-item span {
            color: #666;
            font-size: 14px;
        }
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 13px;
        }
        .footer {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #666;
        }
        .deployment-flow {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Диагностика проекта vsearmyne.ru</h1>
            <p>{{ $project_info['description'] ?? 'Армянский справочник' }}</p>
            <p style="margin-top: 10px; font-size: 14px; color: #999;">
                Версия: {{ $project_info['version'] ?? 'unknown' }} | 
                PHP: {{ $project_info['php_version'] ?? 'unknown' }} | 
                Ветка: {{ $project_info['git_branch'] ?? 'unknown' }} | 
                Коммит: {{ $project_info['git_commit'] ?? 'unknown' }}
            </p>
        </div>

        <div class="grid">
            <div class="card">
                <h2>💾 База данных (текущая)</h2>
                <div class="info-row">
                    <span class="info-label">Статус</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $database_status['status'] }}">
                            {{ strtoupper($database_status['status']) }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Подключение</span>
                    <span class="info-value"><code>{{ $database_status['connection_name'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Драйвер</span>
                    <span class="info-value"><code>{{ $database_status['driver'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Хост</span>
                    <span class="info-value"><code>{{ $database_status['host'] }}:{{ $database_status['port'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">База данных</span>
                    <span class="info-value"><code>{{ $database_status['database'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Пользователь</span>
                    <span class="info-value"><code>{{ $database_status['username'] }}</code></span>
                </div>
                @if($database_status['status'] === 'connected')
                <div class="info-row">
                    <span class="info-label">Таблиц</span>
                    <span class="info-value"><strong>{{ $database_status['tables_count'] }}</strong></span>
                </div>
                @endif
                @if($database_status['error'])
                <div class="error-message">
                    <strong>Ошибка:</strong> {{ $database_status['error'] }}
                </div>
                @endif
            </div>

            <div class="card">
                <h2>☁️ S3 Cloud Storage</h2>
                <div class="info-row">
                    <span class="info-label">Статус</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $s3_status['status'] }}">
                            {{ strtoupper($s3_status['status']) }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bucket</span>
                    <span class="info-value"><code style="font-size: 10px;">{{ $s3_status['bucket'] ?? 'N/A' }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Endpoint</span>
                    <span class="info-value"><code>{{ $s3_status['endpoint'] ?? 'N/A' }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Region</span>
                    <span class="info-value"><code>{{ $s3_status['region'] ?? 'N/A' }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Default Disk</span>
                    <span class="info-value"><code>{{ $s3_status['default_disk'] ?? 'N/A' }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Изображений</span>
                    <span class="info-value"><strong>{{ $environment_info['s3_images_count'] ?? 'N/A' }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Размер</span>
                    <span class="info-value"><strong>{{ $environment_info['s3_storage_size'] ?? 'N/A' }}</strong></span>
                </div>
            </div>

            <div class="card">
                <h2>⚙️ Система</h2>
                <div class="info-row">
                    <span class="info-label">Окружение</span>
                    <span class="info-value"><code>{{ $system_status['app_env'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Debug режим</span>
                    <span class="info-value"><code>{{ $system_status['app_debug'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">APP_URL</span>
                    <span class="info-value"><code style="font-size: 11px;">{{ $system_status['app_url'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cache Driver</span>
                    <span class="info-value"><code>{{ $system_status['cache_driver'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Session Driver</span>
                    <span class="info-value"><code>{{ $system_status['session_driver'] }}</code></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Memory Limit</span>
                    <span class="info-value"><code>{{ $system_status['memory_limit'] }}</code></span>
                </div>
            </div>

            <div class="card">
                <h2>🌐 Окружения</h2>
                <div class="info-row">
                    <span class="info-label">Development</span>
                    <span class="info-value"><strong>Replit (текущий)</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Staging</span>
                    <span class="info-value">
                        <a href="{{ $environment_info['staging_url'] }}" target="_blank" style="color: #667eea;">
                            {{ $environment_info['staging_url'] }}
                        </a>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Production</span>
                    <span class="info-value">
                        <a href="{{ $environment_info['production_url'] }}" target="_blank" style="color: #667eea;">
                            {{ $environment_info['production_url'] }}
                        </a>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">GitHub Repo</span>
                    <span class="info-value">
                        <a href="{{ $environment_info['github_repo'] }}" target="_blank" style="color: #667eea;">
                            armx2020/arm-new
                        </a>
                    </span>
                </div>
                <div class="deployment-flow">
                    📝 {{ $environment_info['deployment_flow'] }}
                </div>
            </div>
        </div>

        <div class="card full-width">
            <h2>📁 Структура проекта</h2>
            
            <div class="structure-section">
                <h3>🔧 Backend ({{ $project_structure['backend']['framework'] }})</h3>
                @foreach($project_structure['backend']['key_directories'] as $path => $description)
                <div class="structure-item">
                    <strong>{{ $path }}</strong>
                    <span>{{ $description }}</span>
                </div>
                @endforeach
            </div>

            <div class="structure-section">
                <h3>🎨 Frontend ({{ $project_structure['frontend']['templating'] }} + {{ $project_structure['frontend']['css'] }} + {{ $project_structure['frontend']['javascript'] }})</h3>
                @foreach($project_structure['frontend']['key_directories'] as $path => $description)
                <div class="structure-item">
                    <strong>{{ $path }}</strong>
                    <span>{{ $description }}</span>
                </div>
                @endforeach
            </div>

            <div class="structure-section">
                <h3>☁️ Storage</h3>
                @foreach($project_structure['storage'] as $key => $value)
                <div class="structure-item">
                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>
                    <span>{{ $value }}</span>
                </div>
                @endforeach
            </div>

            <div class="structure-section">
                <h3>🚀 Deployment</h3>
                @foreach($project_structure['deployment'] as $key => $value)
                <div class="structure-item">
                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>
                    <span>{{ $value }}</span>
                </div>
                @endforeach
            </div>

            <div class="structure-section">
                <h3>✨ Ключевые функции</h3>
                @foreach($project_structure['key_features'] as $feature => $description)
                <div class="structure-item">
                    <strong>{{ $feature }}</strong>
                    <span>{{ $description }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="footer">
            <p>Последнее обновление: {{ $project_info['last_commit_date'] ?? 'unknown' }}</p>
            <p style="margin-top: 10px; font-size: 14px;">
                Эта страница доступна только в development окружении (Replit)
            </p>
        </div>
    </div>
</body>
</html>
