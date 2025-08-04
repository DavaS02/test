<?php
$students = array(
  "John" => 85,
  "Alice" => 95,
  "Bob" => 78
);
$max = max($students);
$name = array_search($max,$students);
echo "Top student : $name with $max";
?> 