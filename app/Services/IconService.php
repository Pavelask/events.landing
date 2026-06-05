<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class IconService
{
    protected string $disk = 'public';
    protected string $directory = 'icons';

    /**
     * Получить список всех иконок из папки с URL для предпросмотра
     */
    public function getAvailableIcons(): Collection
    {
        if (!Storage::disk($this->disk)->exists($this->directory)) {
            return collect();
        }

        $files = Storage::disk($this->disk)->files($this->directory);
        
        return collect($files)
            ->filter(fn ($file) => $this->isImageFile($file))
            ->map(function ($file) {
                $url = Storage::disk($this->disk)->url($file);
                $name = pathinfo($file, PATHINFO_FILENAME);
                
                return [
                    'value' => $name,
                    'label' => $this->formatIconName($name),
                    'url' => $url,
                    'path' => $file,
                    'extension' => pathinfo($file, PATHINFO_EXTENSION),
                ];
            })
            ->sortBy('label');
    }

    /**
     * Получить URL иконки по имени
     */
    public function getIconUrl(string $iconName): ?string
    {
        $path = $this->directory . '/' . $iconName;
        
        // Проверяем с расширениями
        $extensions = ['svg', 'png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($extensions as $ext) {
            $fullPath = $path . '.' . $ext;
            if (Storage::disk($this->disk)->exists($fullPath)) {
                return Storage::disk($this->disk)->url($fullPath);
            }
        }
        
        return null;
    }

    /**
     * Проверить, является ли файл изображением
     */
    protected function isImageFile(string $file): bool
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        return in_array($extension, ['svg', 'png', 'jpg', 'jpeg', 'webp', 'gif']);
    }

    /**
     * Форматировать имя иконки для отображения
     */
    protected function formatIconName(string $name): string
    {
        // Заменяем дефисы и подчёркивания на пробелы
        $formatted = preg_replace('/[-_]/', ' ', $name);
        
        // Делаем первую букву заглавной в каждом слове
        $formatted = ucwords($formatted);
        
        return $formatted;
    }

    /**
     * Загрузить иконку из временного хранилища в постоянное
     */
    public function saveIcon(string $temporaryPath, string $name): string
    {
        $extension = pathinfo($temporaryPath, PATHINFO_EXTENSION);
        $destination = $this->directory . '/' . $name . '.' . $extension;
        
        Storage::disk($this->disk)->copy($temporaryPath, $destination);
        
        return $name;
    }

    /**
     * Удалить иконку
     */
    public function deleteIcon(string $iconName): bool
    {
        $extensions = ['svg', 'png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($extensions as $ext) {
            $path = $this->directory . '/' . $iconName . '.' . $ext;
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
            }
        }
        
        return true;
    }
}
