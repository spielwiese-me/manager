# Spielwiese.me Manager

## Login erzeugen

```bash
read -s pass ; echo -n $pass | sha256sum | head -c 64 > auth.sha256
```

## `managed.json` erstellen

```json
{
    "Beispiel": "/opt/beispiel"
}
```
