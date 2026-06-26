import { test, expect } from '@playwright/test';
import { registerUser } from './auth.helper';

test.describe('Book Editions', () => {
    test.describe.configure({ mode: 'serial' });

    let page;

    test.beforeAll(async ({ browser }) => {
        test.setTimeout(120000);
        const context = await browser.newContext();
        page = await context.newPage();
        await registerUser(page);

        await page.goto('/library/locationtypes');
        await page.getByLabel('New location type').fill('Hardcover');
        await page.getByRole('button', { name: 'Add' }).click();
        await expect(page.locator('table')).toContainText('Hardcover');

        await page.goto('/library/authors/create');
        await page.getByLabel('First Name').fill('Ernest');
        await page.getByLabel('Last Name').fill('Hemingway');
        await page.getByRole('button', { name: 'Create Author' }).click();
        await page.waitForURL(/\/library\/authors/);
        await page.waitForLoadState('networkidle');

        const bookTitle = `Test Book ${Date.now()}`;
        await page.goto('/library/books/create', { waitUntil: 'load' });
        await page.getByLabel('Book Title').fill(bookTitle);

        await page.getByRole('textbox', { name: 'Search Available Authors' }).fill('Hemingway');
        await page.getByRole('textbox', { name: 'Search Available Authors' }).press('Enter');
        await page.waitForTimeout(1000);

        await page.getByRole('button', { name: 'Select author' }).first().click();

        await expect(page.getByText('Ernest Hemingway').first()).toBeVisible();

        await page.getByRole('button', { name: 'Create Book' }).click();
        await page.waitForURL(/\/library\/books/);
        await page.waitForLoadState('networkidle');

        await page.getByRole('link', { name: bookTitle }).click();
        await page.waitForURL(/\/library\/books\/\d+/);
    });

    test('shows editions section with header and add button', async () => {
        await expect(page.getByText('Editions', { exact: true }).first()).toBeVisible();
        await expect(page.getByText('Name').first()).toBeVisible();
        await expect(page.getByText('Format').first()).toBeVisible();
        await expect(page.getByText('Size').first()).toBeVisible();
        await expect(page.getByText('Shelves').first()).toBeVisible();
        await expect(page.getByText('Actions').first()).toBeVisible();
        await expect(page.getByRole('button', { name: 'Add Edition' })).toBeVisible();
    });

    test('can add an edition', async () => {
        await page.getByRole('button', { name: 'Add Edition' }).click();
        await expect(page.getByLabel('Edition Name')).toBeVisible();

        await page.getByLabel('Edition Name').fill('First Edition');
        await page.getByLabel('Edition Size').fill('300');

        await page.getByRole('button', { name: 'Submit' }).click();
        await page.waitForTimeout(1000);

        await expect(page.getByText('First Edition').first()).toBeVisible();
    });

    test('can edit an edition', async () => {
        await page.getByRole('button', { name: 'Edit edition' }).click();
        await page.waitForTimeout(500);

        await page.getByLabel('Edit edition name').fill('Revised Edition');

        await page.getByRole('button', { name: 'Save edition' }).click();
        await page.waitForTimeout(1000);

        await expect(page.getByText('Revised Edition').first()).toBeVisible();
    });

    test('can delete an edition', async () => {
        await page.getByRole('button', { name: 'Delete edition' }).click();
        await page.waitForTimeout(1000);

        await expect(page.getByText('Revised Edition')).not.toBeVisible();
    });
});
