<?php

return [
    'numberToGeneratePerJobRun' => config('app.env') === 'testing' ? 5 : 100,
];
