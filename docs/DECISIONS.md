# Decisions

## Use an illustrative score

The score is intentionally simple: not started is 0, partial is 50, and implemented is 100. The README labels the result clearly so the product does not imply official NIST compliance.

## Keep the strategy boundary

The scoring algorithm is isolated behind ScoringStrategy. This is useful here because a future product could compare a baseline model with a weighted model without changing controllers or the UI.

## Use one API for the first slice

The first version uses a small Laravel API and a React screen in the same application. This keeps local startup simple while preserving a clear boundary for the domain logic.