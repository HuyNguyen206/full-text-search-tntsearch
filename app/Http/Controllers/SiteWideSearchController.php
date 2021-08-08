<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteSearchResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Psy\Util\Str;
use Symfony\Component\Finder\SplFileInfo;

class SiteWideSearchController extends Controller
{
    //
    const BUFFER = 10;

    public function search()
    {
        $keyword = \request()->keyword;
        $toExclude = [
//            'Comment'
        ];
        $files = File::allFiles(app()->basePath() . '/app/Models');

        $modalList = collect($files)->map(function (SplFileInfo $file) {
            $fileName = $file->getRelativePathname();
            if (substr($fileName, -4) !== '.php') {
                return null;
            }
            return substr($fileName, 0, -4);
        })->filter(function (?string $className) use ($toExclude) {
            if ($className === null) {
                return false;
            }
            $reflection = new \ReflectionClass($this->modelNamespacePrefix() . $className);
            $isModel = $reflection->isSubclassOf(Model::class);
            $searchable = $reflection->hasMethod('search');
            return $isModel && $searchable && !in_array($className, $toExclude, true);
        })->map(function ($className) use ($keyword) {
            $model = app($this->modelNamespacePrefix() . $className);
            $fields = array_filter($model::SEARCHABLE_FIELDS, function ($field) {
                return $field !== "id";
            });
            return $model::search($keyword)->get()->map(function ($modelRecord) use ($fields, $className, $keyword) {
                $fieldData = $modelRecord->only($fields);
                $serializedValues = collect($fieldData)->join(' ');

                $searchPos = strpos(strtolower($serializedValues), strtolower($keyword));
                if ($searchPos) {
                    $start = $searchPos - self::BUFFER;
                    $start = $start < 0 ? 0 : $start;
                    $length = strlen($keyword) + 2 * self::BUFFER;
                    $sliced = substr($serializedValues, $start, $length);
                    $shouldAddPrefix = $start > 0;
                    $shouldAddPostFix = ($start + $length) < strlen($serializedValues);
                    $sliced = $shouldAddPrefix ? '...' . $sliced : $sliced;
                    $sliced = $shouldAddPostFix ? $sliced . '...' : $sliced;
                }

                $modelRecord->setAttribute('match', $sliced ?? substr($serializedValues, 0, 2 * self::BUFFER));
                $modelRecord->setAttribute('model', $className);
                $modelRecord->setAttribute('view_link', $this->resolveModelViewLink($modelRecord));
                return $modelRecord;
            });
        })->flatten(1);
        return SiteSearchResource::collection($modalList);
    }

    public function modelNamespacePrefix()
    {
        return app()->getNamespace() . 'Models\\';
    }

    public function resolveModelViewLink(Model $model)
    {
        $mapping = [
            'comment' => '/comments/view/{id}'
        ];
        $modelClass = get_class($model);

        if (Arr::has($mapping, $modelClass)) {
            return URL::to(str_replace('{id}', $model->id, $mapping[$modelClass]));
        }
        $modelName = \Illuminate\Support\Str::plural(Arr::last(explode('\\', $modelClass)));
        $modelName = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::camel($modelName));
        return URL::to('/' . $modelName . '/' . $model->id);
    }
}
