<?php


namespace Cerpus\LaravelAuth;


class AssetManifest {
    /** @var string $manifestRootPath */
    private $manifestRootPath;
    /** @var string $outputRootPath */
    private $outputRootPath;
    /** @var array $manifest */
    private $manifest;

    public function __construct(string $manifestPath, string $manifestRootPath, string $outputRootPath) {
        $this->manifestRootPath = $manifestRootPath;
        $this->outputRootPath = $outputRootPath;

        $this->manifest = json_decode(file_get_contents($manifestPath), true);
    }

    private function manifestPathToOutput(string $manifestPath): string {
        if (strlen($manifestPath) < strlen($this->manifestRootPath)) {
            return $manifestPath;
        }
        if (substr($manifestPath, 0, strlen($this->manifestRootPath)) === $this->manifestRootPath) {
            return $this->outputRootPath . substr($manifestPath, strlen($this->manifestRootPath));
        } else {
            return $manifestPath;
        }
    }

    private function outputPathFilter(array $paths): array {
        return array_map(function ($p) { return $this->manifestPathToOutput($p); }, $paths);
    }
    private function keyToFilenameFilter(array $keys): array {
        return array_map(function ($key) { return $this->manifest[$key]; }, $keys);
    }

    public function getJavascriptFiles(): array {
        $js = array_filter(array_filter(array_keys($this->manifest), function ($key) {
            return substr($key, -3) === '.js';
        }), function ($key) {
            $whitelist = ['main', 'runtime', 'static/js/'];
            foreach ($whitelist as $item) {
                $len = strlen($item);
                if (strlen($key) < $len)
                    continue;
                $match = substr($key, 0, $len);
                if ($match === $item) {
                    return true;
                }
            }
            return false;
        });
        $loading = ['runtime', 'static/js/', 'main'];
        $loading = array_map(function ($item) use ($js) {
            return array_values(array_filter($js, function ($key) use ($item) {
                $len = strlen($item);
                if (strlen($key) < $len)
                    return false;
                $match = substr($key, 0, $len);
                if ($match === $item) {
                    return true;
                }
                return false;
            }));
        }, $loading);
        return $this->outputPathFilter($this->keyToFilenameFilter(array_reduce($loading, function ($a, $b) {
            return array_merge($a, $b);
        }, [])));
    }

    public function getCssFiles(): array {
        return $this->outputPathFilter($this->keyToFilenameFilter(array_filter(array_keys($this->manifest), function ($key) {
            return substr($key, -4) === '.css';
        })));
    }
}
