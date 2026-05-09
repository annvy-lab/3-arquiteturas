# Zoológico PHP: MVC vs MVP vs MVVM

Agora os 3 exemplos têm **interface web simples** e funcionalidades iguais:
- Cadastrar animal
- Filtrar animais por espécie
- Listar animais na tela

## Pastas
- `mvc/`
- `mvp/`
- `mvvm/`

## Executar
Na raiz do projeto:

```bash
php -S localhost:8000
```

Acesse:
- http://localhost:8000/mvc/index.php
- http://localhost:8000/mvp/index.php
- http://localhost:8000/mvvm/index.php

## Diferenças práticas
- **MVC**: Controller trata request e coordena Model + View.
- **MVP**: Presenter concentra regras de fluxo; View é passiva.
- **MVVM**: ViewModel expõe estado pronto para a View renderizar.
