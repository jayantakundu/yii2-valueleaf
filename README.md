# Yii2 Valueleaf
Yii2 Valueleaf
An Valueleaf wrapper as Yii2 component.

## Installation

Add component to `config/main.php`
```php
'components' => [
// ...
'sms' => array (
            'class' => 'app\components\Valueleaf',
        ),
// ...        
],        
```
## Usage

## Send email 
```php
\Yii::$app->sms->send(array('to'=>array('7053xxxxxx'),'message'=>'Sorry you had to cancel your reservation.We hope to EazyBook you soon again with a special deal always! Cheers, EazyConcierge'));
```
