# timezone.php
Bridging the gap between PHP and Javascript

This small helper class allows you to use the DateTime with Javascript date offsets.

For example, if you're looking to get midnight UTC in Australian Eastern Standard time, you can call something like this:

Javascript:
```js
$http.get("http://example.com/mytime.php?offset=" + (new Date()).getTimezoneOffset()
```

```php
<?php

 require("timezone.class.php");
 echo "The offset is: " . $_GET["offset"] . "<br />";
 echo "The time is: " . (new AurorasLive\timezone("midnight", $_GET["offset"]))->format("H:i:sa");
```

Which would then give you:

> The offset is -600

> The time is: 10:00:00am

This class also supports relative time (i.e. "ago" and "from now"):

```php
<?php

echo (new AurorasLive\timezone("now -3 hours"))->ago();

?>
```

Would give you:

> 3 hours ago
