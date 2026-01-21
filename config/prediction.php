<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Prediction API Configuration
    |--------------------------------------------------------------------------
    |
    | These URLs are used for face emotion and text sentiment prediction
    | in the attendance system. The APIs analyze student selfies and diary
    | entries to detect mental health indicators.
    |
    */

    'face_url' => env('PREDICTION_API_FACE_URL', 'https://risetkami-risetkami.hf.space/predict_face'),
    'text_url' => env('PREDICTION_API_TEXT_URL', 'https://risetkami-risetkami.hf.space/predict_text'),
];
