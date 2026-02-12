<?php

return [
    "modelLabel" => "Entradas/Saídas",
    "pluralModelLabel" => "Entradas/Saídas",
    "navigationLabel" => "Entradas/Saídas",
    "navigationGroup" => "Financeiro",
    "forms" => [       
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Nome',
        ],
        "total_value" => [
            "label" => "Valor Total",
            "placeholder" => "Valor Total",
        ],
        "installment_value" => [
            "label" => "Valor da Parcela",
            "placeholder" => "Valor da Parcela",
        ],
        "start_date" => [
            "label" => "Data de Início",
            "placeholder" => "Data de Início",
        ],
        "end_date" => [
            "label" => "Data de Término",
            "placeholder" => "Data de Término",
        ],
        "input" => [
            "label" => "Entrada/Saída",
            "placeholder" => "Selecione",
        ],
        "description" => [
            "label" => "Descrição",
            "placeholder" => "Descrição",
        ],
        "type" => [
            "label" => "Tipo",
            "placeholder" => "Tipo",
        ],      
        'fix' => [
            'label' => 'Fixo?',
            'placeholder' => 'Fixo',
        ],
        'installment_amount' => [
            'label' => 'Quantidade de Parcelas',
            'placeholder' => 'Quantidade de Parcelas',
        ],
        'tags' => [
            'label' => 'Tags',
            'placeholder' => 'Tags',
        ],
    ],
    "columns" => [    
        "name" => "Nome", 
        "total_value" => "Valor Total",
        "installment_value" => "Valor da Parcela",        
        "start_date" => "Data de Início",
        "end_date" => "Data de Término",
        "input" => "Entrada/Saída",
        "description" => "Descrição",
        "type" => "Tipo",  
        'fix' => 'Fixo?',
        'installment_amount' => 'Quantidade de Parcelas',
        'tags' => 'Tags',
    ],

];
