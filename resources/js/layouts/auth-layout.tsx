import { usePage } from '@inertiajs/react';
import AuthCardLayout from '@/layouts/auth/auth-card-layout';
import AuthSimpleLayout from '@/layouts/auth/auth-simple-layout';
import AuthSplitLayout from '@/layouts/auth/auth-split-layout';
import type { AuthLayoutProps } from '@/types';

export type AuthLayoutVariant = 'simple' | 'card' | 'split';

export default function AuthLayout({
    title = '',
    description = '',
    children,
}: AuthLayoutProps) {
    const { authLayout } = usePage<{ authLayout?: AuthLayoutVariant }>().props;

    const LayoutTemplate =
        authLayout === 'card'
            ? AuthCardLayout
            : authLayout === 'split'
              ? AuthSplitLayout
              : AuthSimpleLayout;

    return (
        <LayoutTemplate title={title} description={description}>
            {children}
        </LayoutTemplate>
    );
}
