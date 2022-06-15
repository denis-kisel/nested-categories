<?php

function rebuildNestedCategories()
{

}

function addDateAndFormatToMigrationName(string $name) :string
{
    $date = now()->format('Y_m_d_his');
    return "{$date}_{$name}.php";
}
