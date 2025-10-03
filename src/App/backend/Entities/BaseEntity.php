<?php

abstract class BaseEntity{

    //Used to check if given infos are what we intend
    function CheckIntegrity() : bool{
        return false;
    }
}