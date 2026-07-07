#!/bin/bash
echo "=== ТЕСТ СИСТЕМЫ РЕГИСТРАЦИИ ==="
echo ""

echo "1. Проверка конфигурации Yandex Forms API"
sudo -u apache php artisan tinker --execute="
\$token = config('services.yandex.token');
\$orgId = config('services.yandex.org_id');
\$formId = config('services.yandex.form_id') ?? 'НЕ ЗАДАН';
echo 'Token: ' . (\$token ? substr(\$token, 0, 15) . '...' : 'НЕ ЗАДАН') . PHP_EOL;
echo 'Org ID: ' . (\$orgId ? \$orgId : 'НЕ ЗАДАН') . PHP_EOL;
echo 'Form ID: ' . \$formId . PHP_EOL;
"

echo ""
echo "2. Проверка токена (login.yandex.ru)"
sudo -u apache php artisan tinker --execute="
\$r = Http::withHeaders(['Authorization' => 'OAuth ' . config('services.yandex.token')])->get('https://login.yandex.ru/info');
echo 'Status: ' . \$r->status() . PHP_EOL;
"

echo ""
echo "3. Проверка Forms API (users/me)"
sudo -u apache php artisan tinker --execute="
\$r = Http::withHeaders(['Authorization' => 'OAuth ' . config('services.yandex.token')])->get('https://api.forms.yandex.net/v1/users/me');
echo 'Status: ' . \$r->status() . ' - ' . substr(\$r->body(), 0, 80) . PHP_EOL;
"

echo ""
echo "4. Проверка отправки ответа в форму (createAnswer)"
sudo -u apache php artisan tinker --execute="
\$token = config('services.yandex.token');
\$orgId = config('services.yandex.org_id');
\$formId = '6a46534d90290237129cb245';
\$r = Http::withHeaders([
    'Authorization' => 'OAuth ' . \$token,
    'X-Org-Id' => \$orgId,
    'Content-Type' => 'application/json',
])->withBody(json_encode(['event_id'=>'1','name'=>'TEST_API_CHECK','email'=>'test_check@test.com']), 'application/json')
->post('https://api.forms.yandex.net/v1/surveys/' . \$formId . '/form');
echo 'Status: ' . \$r->status() . PHP_EOL;
if (\$r->successful()) {
    \$json = \$r->json();
    echo 'answer_id: ' . (\$json['answer_id'] ?? 'null') . PHP_EOL;
}
"

echo ""
echo "5. Проверка таблицы form_templates"
sudo -u apache php artisan tinker --execute="
\$t = App\Models\FormTemplate::first();
if (\$t) {
    echo 'ID: ' . \$t->id . PHP_EOL;
    echo 'Name: ' . \$t->name . PHP_EOL;
    echo 'Yandex Form ID: ' . (\$t->yandex_form_id ?? 'null') . PHP_EOL;
    echo 'Questions: ' . count(\$t->questions ?? []) . PHP_EOL;
} else {
    echo 'НЕТ ШАБЛОНОВ!' . PHP_EOL;
}
"

echo ""
echo "6. Проверка мероприятия"
sudo -u apache php artisan tinker --execute="
\$e = App\Models\Event::with('formTemplate')->first();
if (\$e) {
    echo 'Event: ' . \$e->title . PHP_EOL;
    echo 'registration_type: ' . \$e->registration_type . PHP_EOL;
    echo 'form_template_id: ' . (\$e->form_template_id ?? 'null') . PHP_EOL;
    echo 'FormTemplate: ' . (\$e->formTemplate ? \$e->formTemplate->name : 'НЕТ') . PHP_EOL;
    echo 'Yandex Form ID: ' . (\$e->formTemplate->yandex_form_id ?? 'null') . PHP_EOL;
} else {
    echo 'НЕТ МЕРОПРИЯТИЙ!' . PHP_EOL;
}
"

echo ""
echo "7. Проверка маршрута register-anon"
sudo -u apache php artisan route:list --name=register-anon 2>&1

echo ""
echo "8. Проверка Livewire компонента"
sudo -u apache php artisan tinker --execute="
echo 'AnonRegistration: ' . (class_exists('App\Livewire\AnonRegistration') ? 'OK' : 'НЕ НАЙДЕН') . PHP_EOL;
echo 'YandexFormsApi: ' . (class_exists('App\Services\YandexFormsApi') ? 'OK' : 'НЕ НАЙДЕН') . PHP_EOL;
"

echo ""
echo "=== ТЕСТ ЗАВЕРШЁН ==="
