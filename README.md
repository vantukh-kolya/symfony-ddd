# Symfony 7 DDD Demo Project

This repository is a **Symfony 7 demo project** built to showcase **Domain-Driven Design (DDD)**, **Clean Architecture**, and strict **dependency rules** enforced by [Deptrac](https://github.com/qossmic/deptrac).  
It is not a production system - the goal is to demonstrate architecture in Symfony app.

---

## Core Design Principles
- Demonstrate **bounded contexts** (Order BC, Catalogue BC, Shared Kernel).
- Show **layered architecture** (Domain, Application, Infrastructure, Presentation).
- Apply **dependency rules** so Domain remains pure and independent of framework/infrastructure.
- Illustrate cross-BC integration via **ACL (Anti-Corruption Layer)** on the consumer side
  and **OHS (Open Host Service) + Published Language (Contracts)** on the provider side.
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

## Tech Stack

- PHP 8.3
- Symfony 7
- Doctrine ORM
- Deptrac (architecture validation)
