<?php
if (!defined('XHPROF_LIB_ROOT')) {
  define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isAjax = true;
}

if ($_xhprof['ext_name'] && $_xhprof['doprofile'] === true) {
    debug('save profiling data');
    $profiler_namespace = $_xhprof['namespace'];  // namespace for your application
    $xhprof_data = call_user_func($_xhprof['ext_name'].'_disable');
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace, getenv('XHPROF_RUN_NAME') ?: null, $_xhprof);
    if ($_xhprof['display'] === true && PHP_SAPI != 'cli' && !isset($isAjax))
    {
        // url to the XHProf UI libraries (change the host name and path)
        $profiler_url = sprintf($_xhprof['url'].'/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
        echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
    }

    if (getenv('XHPROF_GENERATE_RUN_IMAGE')) {
        ob_start();

        $run = $run_id;
        require __DIR__ . '/../xhprof_html/callgraph.php';

        $content = ob_get_contents();
        ob_end_clean();

        @mkdir(__DIR__ . '/../result');
        file_put_contents(__DIR__ . "/../result/$run_id.png", $content);
    }
}
