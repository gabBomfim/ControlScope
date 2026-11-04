# Architecture

## Components

- Control is the persistence model for the small demo control catalog.
- ControlStatus owns the allowed states and their illustrative point values.
- ScoringStrategy defines the posture calculation boundary.
- IllustrativeScoringStrategy calculates the score, function coverage, and recommendations.
- PostureCalculator loads the current control set and delegates scoring.
- API controllers handle HTTP concerns only.
- React renders the dashboard and sends status updates to the API.

## Data flow

1. The browser requests /api/v1/controls and /api/v1/posture.
2. Laravel validates and filters controls.
3. PostureCalculator delegates to the scoring strategy.
4. The API returns stable resource fields for the table and summary cards.
5. A status change is validated, persisted, and recalculates the summary.

## Tradeoffs

The demo keeps assessment state on the control rows instead of introducing organizations, users, or assessment versions. That keeps the portfolio project focused on scoring rules and information design. A production product would add assessment ownership, history, permissions, and audit events.