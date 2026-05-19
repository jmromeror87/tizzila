USE tizzila_data;

-- 0. Deshabilitar restricciones de llaves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Vaciar tablas (Resetea IDs a 1)
TRUNCATE TABLE poultry_dispatch_route_events;
TRUNCATE TABLE customers;
TRUNCATE TABLE poultry_drivers;
TRUNCATE TABLE poultry_dispatch_route_locations;
TRUNCATE TABLE dispatch_evidences;
TRUNCATE TABLE dispatch_confirmations;
TRUNCATE TABLE poultry_dispatch_route_stops;
TRUNCATE TABLE poultry_dispatch_items;
TRUNCATE TABLE poultry_dispatches;
TRUNCATE TABLE poultry_dispatch_routes;
TRUNCATE TABLE poultry_order_documents;
TRUNCATE TABLE poultry_provider_documents;
TRUNCATE TABLE poultry_provider_document_batches;
TRUNCATE TABLE poultry_order_distributions;
TRUNCATE TABLE poultry_order_approvals;
TRUNCATE TABLE poultry_order_approval_batches;
TRUNCATE TABLE poultry_order_schedules;

-- 2. Volver a habilitar restricciones
SET FOREIGN_KEY_CHECKS = 1;