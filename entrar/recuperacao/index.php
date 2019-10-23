<?php

// Página que envia o e-mail para o usuário com o link para nova/senha/?hash=...&email=...
// hash enviada deve ser
// sha1("{$usuario->getUsername()}{$usuario->getSenha()}Almoçaí__EngSoftProg2019-Texto_Extra_Para_Segurança")
// O texto extra serve para não ser possível a alguém que invadir o BD recriar o hash