export type ControlStatus = 'not_started' | 'partial' | 'implemented';

export type Control = {
    id: number;
    code: string;
    function: string;
    category: string;
    title: string;
    description: string;
    recommendation: string;
    status: ControlStatus;
    status_label: string;
    score: number;
};

export type Posture = {
    score: number;
    coverage: Record<string, number>;
    recommendations: Array<{ code: string; title: string; action: string }>;
    evaluated_controls: number;
    model_note: string;
};