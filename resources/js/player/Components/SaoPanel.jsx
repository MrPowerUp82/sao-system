import React from 'react'

export default function SaoPanel({ children, className = '', hover = true, ...props }) {
    return (
        <div className={`sao-panel ${className}`} {...props}>
            {children}
        </div>
    )
}
