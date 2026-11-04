import React, { useEffect, useMemo, useState } from 'react';
import { createRoot } from 'react-dom/client';
import type { Control, ControlStatus, Posture } from './types';

const statusLabels: Record<ControlStatus, string> = {
    not_started: 'Not started',
    partial: 'Partial',
    implemented: 'Implemented',
};

type WorkspaceView = 'overview' | 'library';

function App() {
    const [controls, setControls] = useState<Control[]>([]);
    const [posture, setPosture] = useState<Posture | null>(null);
    const [view, setView] = useState<WorkspaceView>('overview');
    const [functionFilter, setFunctionFilter] = useState('All functions');
    const [statusFilter, setStatusFilter] = useState('All statuses');
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState<number | null>(null);
    const [error, setError] = useState('');

    const functions = useMemo(
        () => ['All functions', ...Array.from(new Set(controls.map((control) => control.function)))],
        [controls],
    );

    const filteredControls = useMemo(
        () => controls.filter((control) =>
            (functionFilter === 'All functions' || control.function === functionFilter) &&
            (statusFilter === 'All statuses' || control.status === statusFilter)
        ),
        [controls, functionFilter, statusFilter],
    );

    async function load() {
        setLoading(true);
        setError('');
        try {
            const [controlResponse, postureResponse] = await Promise.all([
                fetch('/api/v1/controls'),
                fetch('/api/v1/posture'),
            ]);
            if (!controlResponse.ok || !postureResponse.ok) throw new Error('The dashboard could not load its data.');
            setControls(await controlResponse.json().then((payload) => payload.data));
            setPosture(await postureResponse.json());
        } catch (loadError) {
            setError(loadError instanceof Error ? loadError.message : 'The dashboard could not load its data.');
        } finally {
            setLoading(false);
        }
    }

    async function updateStatus(control: Control, status: ControlStatus) {
        setSaving(control.id);
        setError('');
        try {
            const response = await fetch('/api/v1/controls/' + control.id + '/status', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({ status }),
            });
            if (!response.ok) throw new Error('The control status could not be updated.');
            const updated = await response.json().then((payload) => payload.data);
            setControls((current) => current.map((item) => item.id === updated.id ? updated : item));
            const postureResponse = await fetch('/api/v1/posture');
            setPosture(await postureResponse.json());
        } catch (updateError) {
            setError(updateError instanceof Error ? updateError.message : 'The control status could not be updated.');
        } finally {
            setSaving(null);
        }
    }

    useEffect(() => { void load(); }, []);

    if (loading) return <div className="state-screen">Loading your security posture...</div>;

    return (
        <div className="shell">
            <aside className="sidebar">
                <div className="brand">
                    <span className="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 28 28" role="img">
                            <path d="M14 2.5 24 8v12l-10 5.5L4 20V8l10-5.5Z" />
                            <path d="m9 11 5-3 5 3v6l-5 3-5-3v-6Z" />
                            <path d="M14 8v12M9 11l10 6M19 11 9 17" />
                        </svg>
                    </span>
                    <span>ControlScope</span>
                </div>
                <div className="sidebar-section">
                    <span className="eyebrow">Workspace</span>
                    <button type="button" className={'nav-item ' + (view === 'overview' ? 'active' : '')} onClick={() => setView('overview')} aria-current={view === 'overview' ? 'page' : undefined}>
                        <span className="nav-dot" />Posture overview
                    </button>
                    <button type="button" className={'nav-item ' + (view === 'library' ? 'active' : '')} onClick={() => setView('library')} aria-current={view === 'library' ? 'page' : undefined}>
                        <span className={'nav-dot ' + (view === 'library' ? '' : 'muted')} />Control library
                    </button>
                </div>
                <div className="sidebar-footer"><span className="status-pip" />Demo workspace<small>Illustrative assessment</small></div>
            </aside>

            <main className="main">
                <header className="topbar">
                    <div>
                        <span className="eyebrow">{view === 'overview' ? 'Security program / Q4 2026' : 'Control catalog / 12 curated controls'}</span>
                        <h1>{view === 'overview' ? 'Posture overview' : 'Control library'}</h1>
                    </div>
                    <div className="topbar-meta"><span className="live-dot" />Last recalculated just now</div>
                </header>

                {error && <div className="alert">{error}</div>}

                {view === 'overview' && <section className="summary-grid">
                    <div className="score-card">
                        <div className="card-label">Overall posture</div>
                        <div className="score-row"><strong>{posture?.score ?? 0}</strong><span>/ 100</span></div>
                        <div className="progress-track"><div className="progress-fill" style={{ width: String(posture?.score ?? 0) + '%' }} /></div>
                        <p>Based on {posture?.evaluated_controls ?? 0} curated controls.</p>
                    </div>
                    <div className="metric-card"><span className="card-label">Implemented</span><strong>{controls.filter((control) => control.status === 'implemented').length}</strong><span className="metric-note">of {controls.length} controls</span></div>
                    <div className="metric-card"><span className="card-label">Needs attention</span><strong className="attention">{controls.filter((control) => control.status !== 'implemented').length}</strong><span className="metric-note">prioritized recommendations</span></div>
                    <div className="metric-card"><span className="card-label">Coverage areas</span><strong>{Object.keys(posture?.coverage ?? {}).length}</strong><span className="metric-note">security functions</span></div>
                </section>}

                <section className={'content-grid ' + (view === 'library' ? 'library-grid' : '')}>
                    <div className="panel controls-panel">
                        <div className="panel-heading">
                            <div><span className="eyebrow">Control library</span><h2>{view === 'overview' ? 'Security controls' : 'All controls'}</h2></div>
                            <span className="result-count">{filteredControls.length} shown</span>
                        </div>
                        <div className="filters">
                            <label>Function<select value={functionFilter} onChange={(event) => setFunctionFilter(event.target.value)}>{functions.map((item) => <option key={item}>{item}</option>)}</select></label>
                            <label>Status<select value={statusFilter} onChange={(event) => setStatusFilter(event.target.value)}><option>All statuses</option>{Object.entries(statusLabels).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select></label>
                        </div>
                        <div className="table-wrap">
                            <table><thead><tr><th>Control</th><th>Function</th><th>Status</th><th>Score</th></tr></thead><tbody>
                                {filteredControls.map((control) => <tr key={control.id}>
                                    <td><div className="control-title"><code>{control.code}</code><div><strong>{control.title}</strong><span>{control.category}</span></div></div></td>
                                    <td><span className="function-tag">{control.function}</span></td>
                                    <td><select className={'status-select status-' + control.status} value={control.status} disabled={saving === control.id} onChange={(event) => void updateStatus(control, event.target.value as ControlStatus)}>{Object.entries(statusLabels).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select></td>
                                    <td><span className="score-value">{control.score}</span></td>
                                </tr>)}
                            </tbody></table>
                        </div>
                    </div>

                    {view === 'overview' && <div className="side-column">
                        <div className="panel coverage-panel">
                            <div className="panel-heading compact"><div><span className="eyebrow">By function</span><h2>Coverage</h2></div></div>
                            {Object.entries(posture?.coverage ?? {}).map(([name, value]) => <div className="coverage-row" key={name}><div><span>{name}</span><strong>{value}%</strong></div><div className="progress-track small"><div className="progress-fill" style={{ width: String(value) + '%' }} /></div></div>)}
                        </div>
                        <div className="panel recommendation-panel">
                            <div className="panel-heading compact"><div><span className="eyebrow">Suggested next steps</span><h2>Improve the baseline</h2></div></div>
                            <div className="recommendations">{(posture?.recommendations ?? []).map((item) => <div className="recommendation" key={item.code}><code>{item.code}</code><strong>{item.title}</strong><p>{item.action}</p></div>)}</div>
                        </div>
                    </div>}
                </section>

                <footer className="disclaimer">{posture?.model_note}</footer>
            </main>
        </div>
    );
}

createRoot(document.getElementById('app')!).render(<App />);