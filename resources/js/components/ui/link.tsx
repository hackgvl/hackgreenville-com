import * as React from 'react';
import { Link as InertiaLink } from '@inertiajs/react';
import { cva, type VariantProps } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const linkVariants = cva(
  'inline-flex items-center justify-center transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 cursor-pointer',
  {
    variants: {
      variant: {
        default: 'text-primary underline-offset-4 hover:underline',
        destructive: 'text-destructive underline-offset-4 hover:underline',
        outline:
          'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
        secondary:
          'text-secondary-foreground underline-offset-4 hover:underline',
        ghost: 'hover:bg-accent hover:text-accent-foreground',
        muted:
          'text-muted-foreground hover:text-foreground underline-offset-4 hover:underline',
        navigation: 'text-foreground hover:text-primary transition-colors',
      },
      size: {
        default: 'h-10 px-4 py-2',
        sm: 'h-9 rounded-md px-3',
        lg: 'h-11 rounded-md px-8',
        icon: 'h-10 w-10',
        inline: '',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'inline',
    },
  },
);

export interface LinkProps
  extends React.ComponentProps<typeof InertiaLink>,
    VariantProps<typeof linkVariants> {
  external?: boolean;
}

const Link = React.forwardRef<React.ElementRef<typeof InertiaLink>, LinkProps>(
  ({ className, variant, size, external, children, ...props }, ref) => {
    if (external) {
      return (
        <a
          className={cn(linkVariants({ variant, size, className }))}
          target="_blank"
          rel="noopener noreferrer"
          {...(props as any)}
          ref={ref as any}
        >
          {children}
        </a>
      );
    }

    return (
      <InertiaLink
        className={cn(linkVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      >
        {children}
      </InertiaLink>
    );
  },
);
Link.displayName = 'Link';

export { Link, linkVariants };
