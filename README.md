# Symfony 7 DDD Demo Project

![Tests](https://github.com/vantukh-kolya/symfony-ddd/actions/workflows/app.yml/badge.svg)

This repository is a **Symfony 7 demo project** built to showcase **Domain-Driven Design (DDD)**, **Clean Architecture**, and strict **dependency rules** enforced by **Deptrac**.  
It is not a production system - the goal is to demonstrate architecture in Symfony app.

---


## Project Structure

```
src
├── Catalogue/                     # Catalogue Bounded Context
│   ├── Application/               # Use cases (commands, queries, handlers)
│   │   ├── Command/
│   │   │   ├── CreateProductCommand.php
│   │   │   ├── FulfillStockReservationCommand.php
│   │   │   ├── ReserveStockCommand.php
│   │   │   └── Handler/           # Handlers orchestrating domain logic
│   │   └── Query/
│   │       ├── GetProductsQuery.php
│   │       └── Handler/GetProductsQueryHandler.php
│   │
│   ├── Contracts/                 # Published Language
│   │   └── Reservation/           # DTOs and ports exposed to other BCs
│   │       ├── CatalogueReserveStockRequest.php
│   │       ├── CatalogueReservationResult.php
│   │       ├── ReservationPort.php
│   │       └── ReservationFulfillmentPort.php
│   │
│   ├── Domain/                    # Pure domain model (entities, repos, exceptions)
│   │   ├── Entity/
│   │   ├── Repository/
│   │   └── Exception/
│   │
│   ├── Infrastructure/
│   │   ├── Ohs/                   # Implementations of the Published Language (Contracts)
│   │   │   ├── StockReservationAdapter.php
│   │   │   └── StockReservationFulfillmentAdapter.php
│   │   └── Persistence/Doctrine/  # Doctrine mappings and repositories
│   │
│   └── Presentation/Http/Controller/
│       └── ProductController.php  # API entry points
│
├── Order/                         # Order Bounded Context
│   ├── Application/               
│   │   ├── Command/Handler/
│   │   │   └── CreateOrderCommand.php
│   │   ├── Port/                  # Ports (interfaces, DTOs)
│   │   │   ├── Dto/
│   │   │   │   ├── ReservationRequest.php
│   │   │   │   ├── ReservationResult.php
│   │   │   │   └── FulfillReservationRequest.php
│   │   │   ├── StockReservationPort.php
│   │   │   └── StockReservationFulfillmentPort.php
│   │   └── Query/
│   │
│   ├── Domain/                    # Pure Order model
│   │
│   ├── Infrastructure/            
│   │   └── Persistence/Doctrine/  # Doctrine mappings and repositories
│   │       ├── Mapping/Order.orm.xml
│   │       └── Mapping/OrderItem.orm.xml
│   │
│   ├── Integration/               # Anti-corruption layer to other BCs
│   │   └── Catalogue/
│   │       ├── StockReservationAdapter.php
│   │       └── StockReservationFulfillmentAdapter.php
│   │
│   └── Presentation/              # HTTP/CLI controllers if needed
│
└── SharedKernel/                  # Cross-cutting primitives and services
    ├── Domain/
    │   ├── Persistence/TransactionRunnerInterface.php
    │   └── ValueObject/Money.php
    └── Infrastructure/
        └── Persistence/Doctrine/DoctrineTransactionRunner.php
```

---

## Bounded Contexts

- **Order BC**  
  Manages customer orders, statuses, fulfillment, and reservation workflow.

- **Catalogue BC**  
  Manages products and stock. Provides APIs for stock reservation and committing.

- **Shared Kernel**  
  Common primitives (Value Objects like `Money`, `TransactionRunnerInterface`).


---

## Layers

Each bounded context is split into four layers:

- **Domain**  
  Pure business logic: entities, aggregates, value objects, invariants.  
  No dependencies on Symfony, Doctrine, or infrastructure.

- **Application**  
  Use-cases: Commands, Queries, Handlers, Ports.  
  Orchestrates the Domain, calls external services through ports.

- **Infrastructure**  
  Technical implementations: Doctrine repositories, framework glue.  
  May depend on Symfony, Doctrine, external systems.

- **Presentation**  
  Entry points: HTTP controllers, CLI commands. Call Application use-cases directly.

- **Integration (in Order BC)**  
  Anti-Corruption Layer to integrate with Catalogue. Depends only on Contracts.

- **Contracts (in Catalogue BC)**  
  Published Language (DTOs, service interfaces) to be consumed by other BCs.

---

## Database Transaction Management

Transactions are abstracted via the `TransactionRunnerInterface` in the **Shared Kernel**.
- **Application handlers** orchestrate use-cases inside a transaction.
- **Infrastructure** provides the implementation (e.g. Doctrine).
- This keeps the **Domain** pure and independent of persistence details.

Example (pseudocode):

```php
$this->transactionRunner->run(function () use ($command) {
    $order = Order::create(...);
    $this->orderRepository->add($order);
});
```

## Dependency Rules (Deptrac)

Strict boundaries are enforced by Deptrac.

```
Domain -> SharedDomain only  
Application -> Domain, SharedDomain (and Contracts in Catalogue)  
Infrastructure -> Domain, Application, SharedDomain, Doctrine, Framework  
Presentation -> Application, Framework  
Integration (Order) -> Application, Contracts  
```

See [`deptrac.yaml`](./deptrac.yaml) for full config.

---

## Example: Order flow

**1. Controller → Application**

```php
$command = new CreateOrderCommand($uuid, $amount, $products);
$order = $handler($command);
```

**2. Application → Domain**  
`Order::create()` builds aggregate with items and invariants.

**3. Application → Integration**  
`StockReservationPort.reserve()` delegated to Catalogue Contracts.

**4. Integration → Contracts → Catalogue Application**  
Catalogue validates request and holds product stock.

---

## Cross-BC Communication

- **OrderIntegration** implements `StockReservationPort` using `Catalogue\Contracts\ReservationService`.
- **CatalogueContracts** defines the Published Language.
- This decouples Order from Catalogue’s internals. If Catalogue is extracted into a microservice (REST/Message broker), Order only needs to re-wire the adapter.

## Command / Handler Architecture

The Application layer follows a **Command/Handler** structure **without a command bus or Messenger**:

- **Commands** are simple DTOs carrying scalar input data (e.g. `CreateOrderCommand`).
- **Handlers** are invoked directly (e.g. `$handler($command)`), no bus in between.
- Controllers (or OHS services) construct the Command, validate it, and call the Handler.
- This keeps use-cases explicit and easy to trace while avoiding unnecessary infrastructure overhead.

---

## Application Layer: Commands & Queries

The Application layer is organized around **explicit use-cases** that act as **entry points into the application**:

- **Command/Handler (write side)**
    - Commands are simple DTOs with scalar input (e.g. `CreateOrderCommand`).
    - Handlers orchestrate Domain operations and run inside a transaction.
    - Example: creating an order, reserving stock.

- **Query/Handler (read side)**
    - Queries are DTOs describing a read request (e.g. `GetOrderByIdQuery`).
    - Handlers fetch and return DTOs/arrays optimized for presentation.
    - Example: fetching order details, listing catalogue products.

Controllers or OHS services construct a Command/Query and invoke its Handler directly.  
This makes **Application Handlers the clear entry points for all business use-cases**, while keeping the Domain isolated and pure.
## Tech Stack

- PHP 8.3
- Symfony 7
- Doctrine ORM
- Deptrac (architecture validation)
