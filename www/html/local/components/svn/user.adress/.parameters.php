<?php
$arComponentParameters = array(
    "GROUPS" => array(
        "MAIN" => array(
            "NAME" => GetMessage("svn_parameters_maingroup"),
        ),

    ),
    "PARAMETERS" => array(
        "GET_ALL" => array(
            "PARENT" => "MAIN",
            "NAME" => GetMessage("svn_parameters_active"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "REFRESH" => "N"
        ),
    ),
);
