<?php
namespace common\overrides\rest\interfaces;

/**
 * Interface RestControllerInterface
 * @package common\overrides\rest\interfaces
 */
interface RestControllerInterface
{
    const ACTION_CREATE = 'create';
    const ACTION_INDEX = 'index';
    const ACTION_UPDATE = 'update';
    const ACTION_VIEW = 'view';
    const ACTION_DELETE = 'delete';
}
