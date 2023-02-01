<?php
$arComponentParameters = array(
    "GROUPS" => array(
        "MAIN" => array(
            "NAME" => GetMessage("svn_parameters_maingroup"),
        ),

    ),
    "PARAMETERS" => array(
        "ADD_PROPERTIES_TO_BASKET" => array(
            "PARENT" => "MAIN",
            "NAME" => GetMessage("svn_parameters_active"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "REFRESH" => "N"
        ),
    ),
);
