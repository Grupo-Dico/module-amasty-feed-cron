# GDMexico_AmastyFeedCron

Módulo Magento 2 para ajustar la ejecución del cron de Amasty Feed y reducir carga en servidor.

## Funcionalidad

- Override del cron `amfeed_feed_refresh`
- Ejecución controlada una vez al día
- Optimización de recursos del servidor

## Cron configurado

```cron
0 3 * * *