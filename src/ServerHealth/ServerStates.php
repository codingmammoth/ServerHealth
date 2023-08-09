<?php

class ServerStates {
    final public const ok = 'ok';
    final public const warning = 'warning';
    final public const error = 'error';

    public static function getHighestState(array $states): bool|string
    {
        if (in_array(ServerStates::error, $states)) {
            return ServerStates::error;
        } else if (in_array(ServerStates::warning, $states)) {
            return ServerStates::warning;
        } else if (in_array(ServerStates::ok, $states)) {
            return ServerStates::ok;
        } else {
            return false;
        }
    }
}
