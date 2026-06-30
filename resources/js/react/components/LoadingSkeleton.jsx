export default function LoadingSkeleton({ cards = 6 }) {
    return (
        <div className="skeleton-grid">
            {Array.from({ length: cards }).map((_, index) => (
                <div className="skeleton-card" key={index}>
                    <span />
                    <strong />
                    <em />
                </div>
            ))}
        </div>
    );
}
