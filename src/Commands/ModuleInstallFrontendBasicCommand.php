<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleInstallFrontendBasicCommand extends Command
{
    protected $signature = 'module:install-frontend-basic {name}';
    protected $description = 'Install frontend structure for a module (Inertia React TypeScript structure)';

    public function handle()
    {
        $module = Str::studly($this->argument('name'));

        $this->info("🎨 Installing frontend structure for {$module}...");

        $basePath = resource_path("js/Modules/{$module}");

        $directories = [
            'Pages',
            'Components',
            'Hooks',
            'Services',
        ];

        foreach ($directories as $dir) {
            File::ensureDirectoryExists("{$basePath}/{$dir}");
        }

        $this->createDefaultPages($module);
        $this->createDefaultComponents($module);
        $this->createDefaultHook($module);
        $this->createDefaultService($module);


        $this->info("✅ Frontend structure for {$module} created successfully.");
    }

    protected function createDefaultPages($module)
{
    $pages = ['Index', 'Create', 'Edit', 'Show'];
    $basePath = resource_path("js/Modules/{$module}/Pages");

    $lowerModule = Str::camel($module);
    $kebabModulePlural = Str::kebab(Str::pluralStudly($module)); // for '/products'

    foreach ($pages as $page) {
        $pagePath = "{$basePath}/{$page}.tsx";

        if (!File::exists($pagePath)) {
            $content = '';

            if ($page === 'Create') {
                $content = <<<TSX
import React from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { router } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {$lowerModule}Service from '../Services/{$lowerModule}Service';

const formSchema = z.object({
    name: z.string().min(2, "Name is required"),
    description: z.string().optional(),
});

type FormData = z.infer<typeof formSchema>;

const Create = (props: PageProps) => {
    const { register, handleSubmit, formState: { errors } } = useForm<FormData>({
        resolver: zodResolver(formSchema),
    });

    const onSubmit = async (data: FormData) => {
        await {$lowerModule}Service.create(data);
        router.visit('/{$kebabModulePlural}');
    };

    return (
        <div className="max-w-lg mx-auto space-y-6">
            <h1 className="text-2xl font-bold">Create {$module}</h1>

            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div>
                    <Input placeholder="Name" {...register('name')} />
                    {errors.name && <p className="text-red-500 text-sm">{errors.name.message}</p>}
                </div>

                <div>
                    <Input placeholder="Description" {...register('description')} />
                    {errors.description && <p className="text-red-500 text-sm">{errors.description.message}</p>}
                </div>

                <Button type="submit">Save</Button>
            </form>
        </div>
    );
};

export default Create;
TSX;
            }
            elseif ($page === 'Edit') {
                $content = <<<TSX
import React from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { router } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {$lowerModule}Service from '../Services/{$lowerModule}Service';

const formSchema = z.object({
    name: z.string().min(2, "Name is required"),
    description: z.string().optional(),
});

type FormData = z.infer<typeof formSchema>;

const Edit = (props: PageProps & { id: number; }) => {
    const { register, handleSubmit, formState: { errors } } = useForm<FormData>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            // Prefill existing data here later
            // name: props.name,
        }
    });

    const onSubmit = async (data: FormData) => {
        await {$lowerModule}Service.update(props.id, data);
        router.visit('/{$kebabModulePlural}');
    };

    return (
        <div className="max-w-lg mx-auto space-y-6">
            <h1 className="text-2xl font-bold">Edit {$module}</h1>

            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div>
                    <Input placeholder="Name" {...register('name')} />
                    {errors.name && <p className="text-red-500 text-sm">{errors.name.message}</p>}
                </div>

                <div>
                    <Input placeholder="Description" {...register('description')} />
                    {errors.description && <p className="text-red-500 text-sm">{errors.description.message}</p>}
                </div>

                <Button type="submit">Save</Button>
            </form>
        </div>
    );
};

export default Edit;
TSX;
            }elseif ($page === 'Index') {
                $content = <<<TSX
            import React from 'react';
            import { PageProps } from '@/types';
            import { Button } from '@/components/ui/button';
            import { Pencil, Trash } from 'lucide-react';
            import { router, Link } from '@inertiajs/react';

            type {$module} = {
                id: number;
                name: string;
                description?: string;
            };

            const Index = (props: PageProps & { data: {$module}[], meta?: any }) => {
                const { data, meta } = props;

                const handleEdit = (id: number) => {
                    router.visit('/{$kebabModulePlural}/' + id + '/edit');
                };

                const handleDelete = (id: number) => {
                    if (confirm('Are you sure you want to delete this?')) {
                        router.delete('/{$kebabModulePlural}/' + id);
                    }
                };

                const handlePagination = (url: string) => {
                    if (url) {
                        router.visit(url);
                    }
                };

                return (
                    <div className="space-y-6">
                        <div className="flex justify-between items-center">
                            <h1 className="text-2xl font-bold">{$module} List</h1>
                            <Button onClick={() => router.visit('/{$kebabModulePlural}/create')}>Create New</Button>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full table-auto text-left">
                                <thead className="bg-muted">
                                    <tr>
                                        <th className="p-2">ID</th>
                                        <th className="p-2">Name</th>
                                        <th className="p-2">Description</th>
                                        <th className="p-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.map((item) => (
                                        <tr key={item.id} className="border-b">
                                            <td className="p-2">{item.id}</td>
                                            <td className="p-2">{item.name}</td>
                                            <td className="p-2">{item.description}</td>
                                            <td className="p-2 space-x-2">
                                                <Button size="icon" variant="outline" onClick={() => handleEdit(item.id)}>
                                                    <Pencil size={16} />
                                                </Button>
                                                <Button size="icon" variant="destructive" onClick={() => handleDelete(item.id)}>
                                                    <Trash size={16} />
                                                </Button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        {meta && (
                            <div className="flex justify-between items-center mt-6">
                                <Button
                                    variant="outline"
                                    disabled={!meta.prev_page_url}
                                    onClick={() => handlePagination(meta.prev_page_url)}
                                >
                                    Previous
                                </Button>

                                <span>Page {meta.current_page} of {meta.last_page}</span>

                                <Button
                                    variant="outline"
                                    disabled={!meta.next_page_url}
                                    onClick={() => handlePagination(meta.next_page_url)}
                                >
                                    Next
                                </Button>
                            </div>
                        )}
                    </div>
                );
            };

            export default Index;
            TSX;
            }

            else {
                // Index / Show simple pages
                $content = <<<TSX
import React from 'react';
import { PageProps } from '@/types';

const {$page} = (props: PageProps) => {
    return (
        <div>
            <h1>{$module} {$page} Page</h1>
        </div>
    );
};

export default {$page};
TSX;
            }

            File::put($pagePath, $content);
        }
    }
}

    protected function createDefaultComponents($module)
{
    $lowerModule = Str::camel($module);

    $componentPath = resource_path("js/Modules/{$module}/Components/{$module}Card.tsx");

    if (!File::exists($componentPath)) {
        File::put($componentPath, <<<TSX
import React from 'react';
import { Card, CardContent } from '@/components/ui/card'; // example shadcn
import { {$module} } from '@/types';
import { ShoppingBag } from 'lucide-react'; // Lucide icons

type Props = {
    {$lowerModule}: {$module};
};

const {$module}Card = ({ {$lowerModule} }: Props) => {
    return (
        <Card className="p-4">
            <CardContent>
                <div className="flex items-center gap-2">
                    <ShoppingBag size={20} />
                    <div>
                        <h3 className="font-semibold">{/* {$module} title here */}</h3>
                        <p className="text-sm text-muted-foreground">{/* {$module} subtitle here */}</p>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
};

export default {$module}Card;
TSX
        );
    }
}

    protected function createDefaultHook($module)
    {
        $lowerModule = Str::camel($module);

        $hookPath = resource_path("js/Modules/{$module}/Hooks/use{$module}.ts");

        if (!File::exists($hookPath)) {
            File::put($hookPath, <<<TS
    import { useState, useEffect } from 'react';
    import {$lowerModule}Service from '../Services/{$lowerModule}Service';

    const use{$module} = () => {
        const [data, setData] = useState([]);

        const fetchData = async () => {
            const response = await {$lowerModule}Service.getAll();
            setData(response.data);
        };

        useEffect(() => {
            fetchData();
        }, []);

        return { data };
    };

    export default use{$module};
    TS
            );
        }
    }

    protected function createDefaultService($module)
    {
        $lowerModule = Str::camel($module);

        $servicePath = resource_path("js/Modules/{$module}/Services/{$lowerModule}Service.ts");

        if (!File::exists($servicePath)) {
            File::put($servicePath, <<<TS
    import axios from 'axios';

    const endpoint = '/api/{$this->toKebabCase($module)}s';

    const getAll = () => axios.get(endpoint);
    const getOne = (id: number) => axios.get(\`\${endpoint}/\${id}\`);
    const create = (data: any) => axios.post(endpoint, data);
    const update = (id: number, data: any) => axios.put(\`\${endpoint}/\${id}\`, data);
    const remove = (id: number) => axios.delete(\`\${endpoint}/\${id}\`);

    export default {
        getAll,
        getOne,
        create,
        update,
        remove,
    };
    TS
            );
        }
    }


    protected function toKebabCase($string)
    {
        return Str::kebab(Str::pluralStudly($string));
    }


}
