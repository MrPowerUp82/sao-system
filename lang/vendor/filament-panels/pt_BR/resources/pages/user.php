<?php

return [
     "modelLabel" => "Usuário",
     "pluralModelLabel" => "Usuários",
     "navigationLabel" => "Usuários",
     "navigationGroup" => "Operacional",
     "forms" => [
          "name" => [
               "label" => "Nome",
               "placeholder" => "Nome",
          ],
          "slug" => [
               "label" => "Slug",
               "placeholder" => "Slug",
          ],
          "email" => [
               "label" => "Email",
               "placeholder" => "Email",
          ],
          "phone" => [
               "label" => "Telefone",
               "placeholder" => "Telefone",
          ],
          "document" => [
               "label" => "CPF/CNPJ",
               "placeholder" => "CPF/CNPJ",
          ],
          "avatar_url" => [
               "label" => "Avatar url",
               "placeholder" => "Avatar url",
          ],
          "password" => [
               "label" => "Senha",
               "placeholder" => "Senha",
          ],
          "status" => [
               "label" => "Status",
               "placeholder" => "Status",
               "options" => ["draft" => "Rascunho", "published" => "Publicado"]
          ],
          "description" => [
               "label" => "Descrição",
               "placeholder" => "Descrição",
          ],
          "email_verified_at" => [
               "label" => "Email verified at",
               "placeholder" => "Email verified at",
          ],
          "remember_token" => [
               "label" => "Remember token",
               "placeholder" => "Remember token",
          ],
          'roles' => [
               'label' => 'Funções',
          ]
     ],
     "columns" => [
          "name" => "Nome",

          "slug" => "Slug",

          "email" => "Email",

          "phone" => "Telefone",

          "document" => "CPF/CNPJ",

          "avatar_url" => "Avatar url",

          "password" => "Senha",

          "status" => "Status",

          "description" => "Descrição",

          "email_verified_at" => "Email verified at",

          "remember_token" => "Remember token",

     ],

];
